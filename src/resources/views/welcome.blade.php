<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ninja Sport Stats</title>

        <!-- Include Frontend Application (webpack mix) -->
        <script defer src="/js/manifest.js"></script>
        <script defer src="/js/vendor.js"></script>
        <script defer src="/js/app.js"></script>

    </head>
    <body>
    <div id="root"></div>
    </body>
</html>
