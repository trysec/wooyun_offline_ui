<?php
    include (dirname (__FILE__) . '/config.php');

    $conn   = new MongoClient($config['mongodb']);
    $col    = $conn->wooyun->bugs;

    $cursor = $col->findOne(
        array('wooyun_id' => $_GET['id']),
        array('wy_poc', 'wy_descr', 'wy_detail', 'title'));
    if (! $cursor)
    {
        die ('No such wooyun_id');
    }

    $php_url = preg_replace('/id=.*/', 'id=', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    function pre_process($html)
    {
        global $php_url;

        $html = str_replace ('/css/style.css', 'css/style.css', $html);
        $html = preg_replace ('/\/(images\/(m1|m2|m3|credit)\.png)/', '$1', $html);
        $html = str_replace ('http://www.wooyun.org/bugs/', $php_url, $html);
        return $html;
    }

    if (empty ($cursor['wy_detail']) && empty ($cursor['wy_descr']))
    {
        die ('Bug not open');
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=7"/>
    <title>
      <?= $cursor['title'] ?>
    </title>
    <link href="css/style.css?v=201501291909" rel="stylesheet" type="text/css"/>
    <style>
        .content {
            padding-bottom: 40px;
        }
    </style>
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
  </head>
  <body id="bugDetail">
    <div class="content">
      <h3 class="detailTitle">漏洞名称</h3>
        <p class="detail">
          <?= $cursor['title'] ?>
        </p>
      <h3 class="detailTitle">
        简要描述：
      </h3>
      <p class="detail wybug_description">
        <?= pre_process($cursor['wy_descr']) ?>
      </p>
      <h3 class="detailTitle">
        详细说明：
      </h3>
      <div class='wybug_detail'>
        <?= pre_process($cursor['wy_detail']) ?>
      </div>
      <h3 class="detailTitle">
        漏洞证明：
      </h3>
      <div class='wybug_poc'>
        <?= pre_process($cursor['wy_poc']) ?>
      </div>
      <br style="margin-top: 10px;" />
      <script type="text/javascript">
      $('img').each(function () {
        var src = $(this).attr('src');
        if (src.indexOf('/upload') == 0) {
          $(this).attr('src', 'http://static.wooyun.org/wooyun/' + src);
        }
      })
      </script>
    </body>
  </html>
