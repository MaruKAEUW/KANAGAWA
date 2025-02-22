<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">

    <style>
        #content {
            width: 100%;
            height: 100vh;
            /* background-color: lightblue; */
            text-align: center;
            line-height: 200px;
            font-size: 24px;
            border: 2px solid #000;
            position: relative;
        }
        #download-btn {
            margin-top: 20px;
        }

        #content h1{
            font-family: "Dancing Script", serif; /* Thay đổi font thành font khác từ Google */
    font-weight: 400;
    font-style: normal;
            position: absolute;
        top: 37%;
        left: 47%;
        transform: translate(-50%, -50%);
        color: black; /* Màu chữ để nổi bật */
        font-size: 3em;
        font-weight: bold;    
        }
        #content h5{
            font-family: "Dancing Script", serif; /* Thay đổi font thành font khác từ Google */
    font-weight: 400;
    font-style: normal;
 
            position: absolute;
        top: 47%;
        left: 47%;
        transform: translate(-50%, -50%);
        color: black; 
        font-size: 2em;
        font-weight: bold;    
        }
    </style>
</head>
<body>
    <div id="content">
        <h1>{{ $user->name }}</h1>
        <img src="{{ asset('./images/chungchi_boxung.jpg') }}" alt="" style="width:100%; height:100%;">
        <h5>{{ $course->name }}</h5>
    </div>
 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
       
        const element = document.getElementById("content");

        html2canvas(element).then(canvas => {
            // Convert canvas to data URL in JPEG format
            const dataURL = canvas.toDataURL("image/jpeg", 1.0);

            // Create a link element
            const link = document.createElement("a");
            link.href = dataURL;
            link.download = "exported-image.jpg";
            link.click();
        }).catch(error => {
            console.error("Error exporting HTML to JPG:", error);
        });
    </script>
</body>
</html>
