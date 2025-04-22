<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{institute_name} Certificate</title>
    {basic_header_link}
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;

            font-weight: bold;
            font-size: 19px;
        }

        .text-capitlize {
            text-transform: capitalize;
        }

        .position-relative {
            position: relative;
        }

        .position-absolute {
            position: absolute;
        }

        .w-100 {
            width: 100%;
            display: grid;
        }

        span {
            font-weight: bold;
            color: #1a4891;
            font-size: 14px;
            display: inline-block;
        }

        #photo1 {
            z-index: 999;
            top: 20rem;
            right: 45px;
            width: 110px;
            height: 110px;
        }

        #photo {
            z-index: 999;
            top: 26.5rem;
            right: 45px;
            height: 145px;
            width: 110px;
        }

        .middle-div {
            margin-left: 14rem;
        }

        .test {
            border: 1px solid red
        }
        #center_signature{
            z-index: 999;
            top: 33.7rem;
            left: 12rem;
        }
    </style>
</head>

<body class="position-relative">
    <img id="back-image" class="position-relative" src="{document_path}/franchise_certificate.jpg">
    <?php
    $this->ki_theme->generate_qr($id, 'franchise_certificate', current_url());
    ?>
    <div class="position-absolute" id="photo">
        <img src="upload/images/franchise_certificate_{id}.png">
    </div>
    <div class="position-absolute" id="photo1">
        <img src="upload/{image}" style="width:114px;height:147px">
    </div>
    
    <div class="position-absolute" style="top:46.4%;left:20%;">{name}</div>
    <div class="position-absolute" style="top:51.4%;left:24%;">{institute_name}</div>
    <div class="position-absolute" style="top:60.7%;left:16%;">{center_full_address}</div>
    <div class="position-absolute" style="top:65.7%;left:13.5%;">{state}</div>
    <div class="position-absolute" style="top:65.7%;left:36%;">{city}</div>
    <div class="position-absolute" style="top:65.7%;left:70.5%;">{pincode}</div>

    <div class="position-absolute" style="top:70.4%;left:19.5%;">{center_number}</div>
    <div class="position-absolute" style="top:70.4%;left:45.5%">{valid_upto}</div>
    <div class="position-absolute" style="top:70.4%;left:68.5%">{certificate_issue_date}</div>
    
    
    <!-- <div class="position-absolute w-100" style="top:28.5%;padding-left:0px;z-index:9999">

        <div class="middle-div" style="">{center_number}</div>

        <div class="middle-div" style="margin-top:0.5rem;">{city}, &nbsp;{state}</div>
        <div class="middle-div" style="margin-top:0.3rem">{name}</div>
        <div class="middle-div" style="margin-top:.85rem;">{certificate_issue_date}</div>
        <div class="middle-div" style="margin-top:-1rem;margin-left:69%">{valid_upto}</div>

    </div> -->

</body>

</html>