<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>مشروع الأيتام</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
<style>
    body {
    direction: rtl;
    text-align: right;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    position: relative;
    background-color: 	#ffb342; /* لون الخلفية حول الصورة */
}

body::before {
    content: "";
    background-image: url('{{ asset('images/welcome.png') }}');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 1;
    z-index: -1;
}

</style>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="{{ route('orphans.index') }}">الأيتام</a>
  </div>
</nav>
<div class="container">@yield('content')</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
