<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PDF</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <!-- Flipbook StyleSheet -->
    <link href="{{ asset('flipbook/css/min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons Stylesheet -->
    <link href="{{ asset('flipbook/css/themify-icons.min.css') }}" rel="stylesheet" type="text/css">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

    <div id="flipbookContainer">
    </div>


    <!-- jQuery  -->
    <script src="{{ asset('flipbook/js/libs/jquery.min.js') }}" type="text/javascript"></script>
    <!-- Flipbook main Js file -->
    <script src="{{ asset('flipbook/js/dflip.min.js') }}" type="text/javascript"></script>
    <!-- Flipbook main Js file -->
    <script>
        jQuery(document).ready(function () {
            // Construct the full PDF URL with Blade
            var pdf = 'https://inventarisreg.awalbros-hospital.com/asset-inventaris/storage/flipbooks/{{ $nama->NamaFile }}.pdf';

            var options = {
                height: 2000,
                duration: 700,
                backgroundColor: "#2F2D2F"
            };

            var flipBook = $("#flipbookContainer").flipBook(pdf, options);
        });
    </script>


</body>

</html>