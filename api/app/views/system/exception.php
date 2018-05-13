<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="en-us" />

    <title>Wave Framework - An Error Occured :(</title>

    <!-- Use boostrap to sex this up a bit -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap.no-icons.min.css" rel="stylesheet">
</head>

<body>

<div class="container">
    <?php $code = $exception->getCode(); ?>

    <h2>Oh, no. Something is wrong here.</h2>
    <p>Sorry about this, something happened that wasn't supposed to.</p>

    <?php if(\Wave\Core::$_MODE == \Wave\Core::MODE_DEVELOPMENT): ?>
        <h6>Tech stuff</h6>
        <p><code><?php echo $code; ?>: <?php echo $exception->getMessage(); ?></code></p>

        <h6>Backtrace</h6>
        <pre style="overflow-x:scroll; white-space:pre;"><?php debug_print_backtrace(); ?></pre>

    <?php endif; ?>

</div>


</body>
</html>