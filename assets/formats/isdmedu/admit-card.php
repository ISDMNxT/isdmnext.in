<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{student_name} Card</title>
    <!-- <link href="{base_url}assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="{base_url}assets/css/style.bundle.css" rel="stylesheet" type="text/css" /> -->
    {basic_header_link}
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
        
            font-weight: bold;
        }
        .text-capitlize{
            text-transform: capitalize;
        }
        .position-relative{
            position: relative;
        }
        .position-absolute{
            position: absolute;
        }
        .w-100{
            width: 100%;
        }
        td,p{
            font-weight: bold;
            color:black;
            font-size: 13px;
            line-height:1.815;
            /* text-transform: capitalize; */
            /* background-color: black; */
        }
        #photo{
            z-index: 999;
            top:16.77rem;
            right:20.2px;
            width: 120px !important;;
            height: 95px;
        }
    </style>
</head>

<body class="position-relative">
    <img id="back-image" class="position-relative" src="{document_path}/admit-card.jpg">
    <div class="position-absolute" id="photo">
        <img src="upload/{image}" style="width:90px;height:90px">
    </div>
    <div class="position-absolute" style="top:1.8%;left:6.3%">
       <img src="upload/images/admit_card_{admit_card_id}.png" style="width: 104px;height:104px;" alt="">
    </div>
     <p class="position-absolute" style="top:9.2%;left:15%">{center_full_address}</p>
    <p class="position-absolute" style="top:19.4%;left:13%">{student_name}</p>
    <!-- <p class="position-absolute" style="top:22.3%;left:25%">{student_name}</p> -->
    <p class="position-absolute" style="top:21.5%;left:20%">{father_name}</p>
    <p class="position-absolute" style="top:23.6%;left:20.5%">{enrollment_no}</p>
    <p class="position-absolute" style="top:23.6%;left:53%">{roll_no}</p>
    <p class="position-absolute" style="top:28.6%;left:14%">{course_name}</p>
    <!-- <p class="position-absolute" style="top:28%;left:25%">{date}</p>
    <p class="position-absolute" style="top:29.9%;left:25%">{time}</p> -->
    <p class="position-absolute" style="top:30.7%;left:18.5%">{center_name}</p>
    <p class="position-absolute" style="top:32.9%;left:17.8%">{exam_date}</p>
    
    <div class="position-absolute" style="top:38.5%;left:77.7%">
        <img style="width: 96px;height:70px;" src="upload/{c_signature}" alt="">
    </div>

</body>

</html>