<html>
<p>This is going to be ignored by PHP and displayed by the browser.</p>
<?php echo "<script type='text/javascript'>alert('Welcome!')</script>"; ?>
<p>This will also be ignored by PHP and displayed by the browser.</p>
<body>
    <form action="login.php" method="post">
    <fieldset>
        <div class="form-group">
            <input autocomplete="off" autofocus class="form-control" name="username" placeholder="Username" type="text"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="Password" type="password"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Log In
            </button>
        </div>
    </fieldset>
</form>
<div>
    or <a href="register.php">register</a> for an account
</div>

</body>

</html>
