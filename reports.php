<?php
require('DBconfig.php');
session_start();
if(!isset($_SESSION["username"])){
    header("Location: /login");
    die();
}

if (isset($_POST['DownloadReport'])) {
    $fromDate = $toDate = "";
    $fromDate = $_POST['FromDate'];
    $toDate = $_POST['ToDate'];

    $result = $connDB->query("SELECT * FROM 
    (
    (SELECT DATE_FORMAT(bloombyte_enquiry.Date,'%d-%m-%Y') as 'reg_date',name,emailid,mobile,location,business,country,message FROM bloombyte_enquiry
    WHERE CAST(bloombyte_enquiry.Date as date) BETWEEN '$fromDate' AND '$toDate' ORDER BY ID DESC)
    UNION ALL
    (SELECT DATE_FORMAT(bloombyte_faq.Date,'%d-%m-%Y') as 'reg_date','' as 'name',email_id as 'emailid','' as 'mobile','' as 'location','' as 'business',
    '' as 'country',comments as 'message' FROM bloombyte_faq
    WHERE CAST(bloombyte_faq.Date as date) BETWEEN '$fromDate' AND '$toDate'
    ORDER BY ID DESC)
    ) A; ");

    $CurDate = Date('Y-m-d');
    if ($result->num_rows > 0) {
        $headers = ['Registered Date', 'Name', 'Email', 'Mobile No', 'Location', 'Country', 'Business', 'Message'];
        $fp = fopen('php://output', 'w');
        if ($fp && $result) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="Bloombyte_Enquiry"' . $CurDate . '".csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            fputcsv($fp, $headers);
            while ($data = $result->fetch_assoc()) {
                fputcsv($fp, array_values($data));
            }
            die;
        }
    }
} elseif (isset($_POST['GetReport'])) {
    $fromDate = $toDate = "";
    $fromDate     = date("Y-m-d", strtotime($_POST['FromDate']));
    $toDate     = date("Y-m-d", strtotime($_POST['ToDate']));
    $dataHead = "";
} else {
    $fromDate = $toDate = "";
    $fromDate = date('Y-m-d', strtotime('-10 days'));
    $toDate = date("Y-m-d");
    $dataHead = "";
}

$result = $connDB->query("SELECT * FROM 
(
(SELECT DATE_FORMAT(bloombyte_enquiry.Date,'%d-%m-%Y') as 'reg_date',name,emailid,mobile,location,business,country,message FROM bloombyte_enquiry
WHERE CAST(bloombyte_enquiry.Date as date) BETWEEN '$fromDate' AND '$toDate')
UNION ALL
(SELECT DATE_FORMAT(bloombyte_faq.Date,'%d-%m-%Y') as 'reg_date','' as 'name',email_id as 'emailid','' as 'mobile','' as 'location','' as 'business',
'' as 'country',comments as 'message' FROM bloombyte_faq
WHERE CAST(bloombyte_faq.Date as date) BETWEEN '$fromDate' AND '$toDate'
)
) A ORDER BY reg_date DESC");


if ($result->num_rows > 0) {
    $dataHead .= "<div class='list-view-rounded-corners'>";
    $dataHead .= "<table border='1' class='table table-bordered mt-3 table-responsive' style='overflow-x:auto;'>";
    $dataHead .= "<thead class='bgcolor'>";
    $dataHead .= "<tr>";
    $dataHead .= "<th>S.No.</th>";
    $dataHead .= "<th>Registered Date</th>";
    $dataHead .= "<th>Name</th>";
    $dataHead .= "<th>Email</th>";
    $dataHead .= "<th>Mobile No.</th>";
    $dataHead .= "<th>Location</th>";
    $dataHead .= "<th>Country</th>";
    $dataHead .= "<th>Business Name</th>";
    $dataHead .= "<th>Message</th>";
    $dataHead .= " </tr>";
    $dataHead .= "</thead>";
    $dataHead .= "<tbody>";
    $dataHead .= "</div>";
    $i = 1;
    while ($data = $result->fetch_assoc()) {
        $dataHead .= "<tr>";
        $dataHead .= "<td>" . $i . "</td>";
        $dataHead .= "<td>" . $data['reg_date'] . "</td>";
        $dataHead .= "<td>" . $data['name'] . "</td>";
        $dataHead .= "<td>" . $data['emailid'] . "</td>";
        $dataHead .= "<td>" . $data['mobile'] . "</td>";
        $dataHead .= "<td>" . $data['location'] . "</td>";
        $dataHead .= "<td>" . $data['country'] . "</td>";
        $dataHead .= "<td>" . $data['business'] . "</td>";
        $dataHead .= "<td>" . $data['message'] . "</td>";
        $dataHead .= "</tr>";
        $i = $i + 1;
    }
    $dataHead .= "</tbody>";
    $dataHead .= "</table>";
    $htmlprintf = $dataHead;
} else {
    $htmlprintf = "<div class='list-view-rounded-corners pt-5'><p style='text-align: center'><b>No Results Found!</b></p></div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloombyte</title>
    <link href="images/favicon.png" rel="icon">
    <link rel="stylesheet" href="/style/report.css">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous">
    </script>
    <script> 
    $(function(){
    $("#footer").load("footer.html"); 
    });
    </script>
<body>
<section class="home" id="header">
        <div id="imgloads">
        <div class="title-content">
            <nav class="navbar navbar-expand-md "> 
                <a href="/index.html" class="navbar-logo"><img src="images/Group 3.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> 
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                       <div class="item-1">
                        <li class="nav-item"><a class="nav-link" href="/index.html">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/e-commers.html">Bloombyte Store</a></li>
                        <li class="nav-item"><a class="nav-link" href="/contact.html">Contact</a></li>
                       </div>
                       <div class="item-2">
                        <a href="https://bloombyte.io/req-demo.html"><button class="btn-1">Request a Demo</button></a>
                       </div>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    </section>
    <section>
    <div id="divbody" class="container">
        <h1 id="header-title" class="pt-5 pb-3">Enquiry Report</h1>
    <div class="card">           
        <div class="card-body">
            <form name="dailyreportform" action="/reports.php" method="post">
            <div class="row form-group mb-3">
            <div class="col-sm-4 col-md-2 col-lg-2 lbl"><label class="form-label">From Date * :</label></div>
            <div class="col-sm-8 col-md-3 col-lg-3"><input type="date" id="FromDate" name="FromDate" data-date-format="dd-mm-yyyy" class="form-control" value="<?php if (isset($_POST['FromDate'])){  
            echo $_POST['FromDate'];} else {echo date('Y-m-d', strtotime('-10 days'));} ?>"></div>
            </div>
            <div class="row form-group mt-15">
            <div class="col-sm-4 col-md-2 col-lg-2 lbl"><label class="form-label">To Date * :</label></div>
            <div class="col-sm-8 col-md-3 col-lg-3"><input type="date" id="ToDate" name="ToDate" data-date-format="dd-mm-yyyy" class="form-control" value="<?php if (isset($_POST['ToDate'])) {
            echo $_POST['ToDate'];} else { echo date("Y-m-d");
            } ?>"></div>
            </div>
            <div class="row mt-3">    
            <div class="col-sm-4 col-md-2 col-lg-2 lbl">&nbsp;</div>
            <div class="col-sm-8 col-xs-8 col-md-8 col-xl-8 text-right">
            <button id="GetReport" class="btn btn-primary button" name="GetReport" type="submit">Show</button>  
            <button id="DownloadReport" class="btn btn-primary button" name="DownloadReport" type="submit">Download</button>        
            </div>
            </div>
            </form>
        </div>
   </div>
        <div class="row">
            <?php if (isset($htmlprintf)) {
                echo $htmlprintf;
            } ?>
        </div>
    </div>    
        </section>
    <section >
    <div id="footer"></div>
    </section>
</body>
</html>