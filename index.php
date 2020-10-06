<?php
    $u=$_SERVER['QUERY_STRING'];
    function cs($u){
        $id;
        $f=explode("&",$u);
        foreach ($f as $i){
            $a=explode("=",$i);
            $id[$a[0]]=$a[1];
        }
    
        return $id;
    }
    $id=cs($u);
    
    $djs=false;
    $json;
    if(!is_null($id['id'])){
        
        $file = fopen("data.txt", "r") or die("Unable to open file!");
        $res = fread($file,filesize("data.txt"));
        $j = json_decode($res,true);
        fclose($file);
        foreach ($j as $a){
            if($a['id']==$id['id']){
                $json=$a;
                break;
            }
        }
        if(!is_null($json)){
            $djs=true;
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>时钟</title>
    <style>
        .app {
            position: fixed;
            width: 100%;
            height: 100%;
        }

        .time {
            width: 100%;
            margin: auto;
            text-align: center;
            padding-top: 120px;
            font-size: 11rem;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
        }

        .date {
            width: 100%;
            text-align: center;
            padding-top: 40px;
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="time" id="time"></div>
        <div class="date">
            <span id="date"></span> <span id="week"></span>
        </div>
        <?php 
        if($djs){
            echo(
            '<div class="date">
                <span>'.$json['name'].'</span> <span id="day"></span>
            </div>'
            );
        }
        ?>
    </div>


    <script>
        var f = 0;
        var d = 0;
        
        var i = 0;
        var change = false;
        var t=setInterval(getTime,i*1000);
        function getTime() {
            var date = new Date();
            var utc8DiffMinutes = date.getTimezoneOffset() + 480;
            date.setMinutes(date.getMinutes() + utc8DiffMinutes);
            if (date.getMinutes() !== f) {
                f = date.getMinutes();
                var timeString = date.getHours() + ':' + ('0' + f).slice(-2);
                var dateString = (date.getMonth() + 1) + '月' + date.getDate() + '日';
                var weekList = ['日', '一', '二', '三', '四', '五', '六'];
                // var weekJpList = [' 日曜日', ' 月曜日', ' 火曜日', ' 水曜日', ' 木曜日', ' 金曜日', ' 土曜日'];
                var weekString = '星期' + weekList[date.getDay()];
                // var weekString =  weekJpList[date.getDay()];
                <?php 
                    if($djs){
                        $da=strtotime($json['date']);
                        echo('if(d!=date.getDay()){
                        d=date.getDay();
                        document.getElementById("day").innerHTML = "倒计时"+Math.floor((('.$da.'-(new Date().getTime()/1000))/(24*3600))+1)+"天";
                        }');
                    }
                ?>
                document.getElementById("time").innerHTML = timeString;
                document.getElementById("date").innerHTML = dateString;
                document.getElementById("week").innerHTML = weekString;
                
                i = 60 - date.getSeconds();
                if(!change || i < 60){
                    clearInterval(t);
                    t=setInterval(getTime,i*1000);
                    if(i===60){
                        change = true;
                    }else{
                        change = false;
                    }
                }
            }
        }

    </script>
</body>

</html>
