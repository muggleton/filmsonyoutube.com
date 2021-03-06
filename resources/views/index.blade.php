<!DOCTYPE html>
<html lang="en" ng-app="filmsonyoutube">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Free links to popular full length movies hosted on YouTube.">

  <title ng-controller="titleController" ng-bind="Page.title()"></title>
  <link rel="stylesheet" href="/assets/css/app.css" />


  <base href="/">

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>

      <nav class="navbar navbar-default navbar-fixed-top" id="topbar" ng-controller="navigationController">
        <div class="container-fluid">
          <div class="navbar-header">
            <button ng-show="isCurrent('/')" ng-cloak type="button" class="navbar-toggle" data-toggle="sidebar" data-target=".search-sidebar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a ng-show="!isCurrent('/')" ng-cloak href="/" class="navbar-home"><i class="glyphicon glyphicon-home"></i></a>
            <a class="navbar-brand" href="/"><img class="navbar-logo"
              src="/assets/img/logo.png"></a>
            </div>
          </div>
        </nav>

      </div>
      <div class="container-fluid" id="view" ng-view>

      </div>
      
      @yield('content')

      <script src="/assets/js/vendor/vendor.js"></script>
      <script src="/assets/js/vendor/angular-route.min.js"></script>
      <script src="/assets/js/app.js"></script>
      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-65365815-1', 'auto');
        ga('send', 'pageview');
      </script>
    </body>
    </html>