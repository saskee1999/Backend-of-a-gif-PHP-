<?php

    $allow_url_fopen = true;
    $targetDir = 'uploads/';
    $inputURL = $_GET['gifURL'];
    $filename = basename($inputURL);
    $targetFile = $targetDir.$filename;
    $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $imgInfo = pathinfo($targetFile);
    $flag = 1;
    $flag2=1;

    //first we have to check if this is a valid gif image URL
    //First check if the input is an image

    if(is_array(getimagesize($inputURL)))
    {
        //check if gif
        if($ext != 'gif')
        {
            echo "given url does not contain a gif";
            $flag = 0;
        }
    }
    else
    {
        echo "This is not an image!";
        $flag = 0;
    }


    //check size
    $img = get_headers($inputURL, 1);
    $imgSize =  $img['Content-Length'];
    $maxSize = 9.5*1024*1024; //9.5MB
    if($imgSize > $maxSize)
    {
        echo "This image is too big";
        $flag = 0;
    }

    //check for duplicates

    if(file_exists($targetFile))
    {
        echo "This file already exists!";
        $flag2=0;
    }
    

    if($flag == 1) //successful upload
    {
        if($flag2==0)
        {
            $outputImage = $targetDir.$imgInfo['filename'].'Cover.jpg';
        }
        else{

        
        file_put_contents($targetFile, file_get_contents($inputURL));
        $tmpImage = imagecreatefromgif($targetFile);
        $outputImage = $targetDir.$imgInfo['filename'].'Cover.jpg';
        imagejpeg($tmpImage, $outputImage, 100);
        }
    }
    

?>


<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
        .container {
        position: relative;
        width: 100%;
        max-width: 400px;
        }

        .container img {
        width: 100%;
        height: auto;
        }

        .container .btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        background-color: #555;
        color: white;
        font-size: 16px;
        padding: 12px 24px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        text-align: center;
        }

        .container .btn:hover {
        background-color: black;
        }
        </style>
    </head>
    <div class = "container">
    <img src="<?php echo $outputImage ?>" id='cover' onclick = 'toggle()' onmouseout="stop()" />
    <button id="btn" class = "btn" onclick='hidebutt()'>Button</button>
    </div>

    <script>
        var image = document.getElementById("cover");
        function toggle()
        {
            if(image.getAttribute('src') == '<?php echo $targetFile?>')
            {
                image.src = '<?php echo $outputImage?>';
                document.getElementById("btn").style.visibility = "visible";
            }
        }

        function stop()
        {
            image.src = '<?php echo $outputImage?>';
            document.getElementById("btn").style.visibility = "visible"
        }

        function hidebutt()
        {
            document.getElementById("btn").style.visibility = "hidden";
            image.src = '<?php echo $targetFile?>'
        }

    </script>
</html>
