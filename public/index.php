<?php

use GuzzleHttp\Client;
use WhatJoomlaTemplateIsThat\Connect;
use WhatJoomlaTemplateIsThat\TemplateInfoParser;
use WhatJoomlaTemplateIsThat\TemplateNameFinder;
use WhatJoomlaTemplateIsThat\UrlBuilder;

$url = '';
$errorMessage = '';
$error = false;

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    include dirname(__DIR__).'/vendor/autoload.php';

    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_SPECIAL_CHARS);
    $client = new Client();

    try {
        $connect = new Connect($client);
        $pageContent = $connect->getPage($url);

        $tnFinder = new TemplateNameFinder($pageContent);
        $templateName = $tnFinder->getTemplateName();

        if (empty($templateName)) {
            throw new Exception('Not Joomla! site');
        }

        $urlBuilder = new UrlBuilder($url, $templateName);
        $templateInfo = new TemplateInfoParser($client, $urlBuilder->templateDetails());
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        $error = true;
    }
}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>What Joomla! Template Is That?</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://v4-alpha.getbootstrap.com/examples/narrow-jumbotron/narrow-jumbotron.css">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <style>
      .img-fluid {
        border: 1px solid;
      }
    </style>
  </head>
  <body>
    <section class="container">
    <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills float-right">
            <li class="nav-item">
              <a class="nav-link active" href="./">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="https://github.com/mklkj/whatjoomlatemplateisthat">GitHub</a>
            </li>
            </li>
          </ul>
        </nav>
        <h3 class="text-muted">What Joomla! Template Is That?</h3>
      </div>

      <form class="jumbotron" method="post">
        <h1 class="display-4">What Joomla! Template Is That?</h1>
        <p class="lead">What Joomla! Template Is That is a free online tool allows you to easily detect what Joomla! template a site uses.
        <div class="input-group">
          <input name="url" class="form-control" placeholder="Enter URL or domain to search..." value="<?=$url;?>" type="text">
          <span class="input-group-btn">
            <button class="btn btn-success" id="check" type="submit">Go!</button>
          </span>
        </div>
      </form>

      <?php if ('POST' === $_SERVER['REQUEST_METHOD'] and false === $error): ?>
        <hr>
        <div class="card">
          <h4 class="card-header">Template details</h4>
          <div class="card-block">
            <table class="table">
              <tr>
                <th>Template Name</th>
                <td><?=$templateInfo->getName();?></td>
              </tr>
              <tr>
                <th>Creation Date</th>
                <td><?=$templateInfo->getCreationDate();?></td>
              </tr>
              <tr>
                <th>Author</th>
                <td><?=$templateInfo->getAuthor();?></td>
              </tr>
              <tr>
                <th>Author Email</th>
                <td><?=$templateInfo->getAuthorEmail();?></td>
              </tr>
              <tr>
                <th>Author Homepage</th>
                <td>
                  <a target="_blank" href="<?=$templateInfo->getAuthorHomepage();?>">
                    <?=$templateInfo->getAuthorHomepage();?></a>
                  </td>
              </tr>
              <tr>
                <th>Copyright</th>
                <td><?=$templateInfo->getCopyright();?></td>
              </tr>
              <tr>
                <th>Description</th>
                <td><?=$templateInfo->getDescription();?></td>
              </tr>
              <tr>
                <th>Version</th>
                <td><?=$templateInfo->getVersion();?></td>
              </tr>
              <tr>
                <th>Template Screenshot</th>
                <td><a target="_blank" href="<?=$urlBuilder->preview();?>">
                  <img class="img-fluid" src="<?=$urlBuilder->preview();?>" alt="">
                </a></td>
              </tr>
              <tr>
                <td>Template Details</td>
                <td>
                  <a target="_blank" href="<?=$urlBuilder->templateDetails();?>">
                    <?=$urlBuilder->templateDetails();?>
                  </a>
                </td>
              </tr>
            </table>
          </div>
        </div>
      <?php elseif (true === $error): ?>
        <div class="alert alert-warning" role="alert">
            <strong>Warning!</strong> Bad address or other error.
            Try use address like <code>https://joomla.org/</code> without <code>index.php</code> at the end and try again.
            <details><?=$errorMessage;?></details>
        </div>
      <?php endif ?>
      <footer class="footer">
        <p>© <a href="https://github.com/mklkj">mklkj</a> 2017</p>
      </footer>
    </section>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-99646499-1', 'auto');
      ga('send', 'pageview');

    </script>
  </body>
</html>
