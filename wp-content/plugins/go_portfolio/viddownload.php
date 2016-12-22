<?php
    $name = $_GET['filename'];
    $id = $_GET['id']; //the youtube video ID
    $format = $_GET['fmt']; //the MIME type of the video. e.g. video/mp4, video/webm, etc.
    parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=".$id),$info); //decode the data
    if(isset($info['url_encoded_fmt_stream_map'])){
        $streams = $info['url_encoded_fmt_stream_map']; //the video's location info
        $streams = explode(',',$streams);

        foreach($streams as $stream){
            parse_str($stream,$data); //decode the stream
        
            if(stripos($data['type'],$format) !== false){ //We've found the right stream with the correct format
                $file_url = $data['url'];
                header('Content-Type: video/mp4');
                header("Content-Transfer-Encoding: Binary"); 
                header("Content-disposition: attachment; filename=\"" . $name.'.mp4' . "\""); 
                readfile($file_url); // do the double-download-dance (dirty but worky)
            }
        }
    }else{
        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
    }
    
?>
