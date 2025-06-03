<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    @page {
        margin: 0px;
        size: auto;

    }

    body {
        margin-top: 8px;
        margin-left: 10px;

    }

    .container {
        margin-left: 10;
        font-family :Verdana, Geneva, Tahoma, sans-serif;
        font-style: :bold;
        scale : 100%;

    }
</style>


<body>
    <div class="container">
        <center>
        <table border="0" style="width: 100%; height:100%; border-spacing: 0px; align:center;">
            <tbody>
                <tr>
                    <td rowspan="5" style="text-align: left; margin-left: 0px;" width="20%"><img src="data:image/png;base64, {!! $qrcode !!}" width="50" height="50"></td>

                </tr>

                <tr>

                    <td colspan="2" style="font-size: 13px;" height="15px" width="10px"></td>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$query->real_name}}</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 9px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $query->no_sn }}</td>
                </tr>

            </tbody>
        </table>
        </center>
        <div class="row mt-5 text-center">

        </div>
    </div>
</body>

</html>
