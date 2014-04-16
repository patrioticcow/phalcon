<?php

$LOCAL_ROOT      = "/usr/share/nginx/html/phalcon";
$LOCAL_REPO_NAME = "phalcon";
$LOCAL_REPO      = "{$LOCAL_ROOT}/{$LOCAL_REPO_NAME}";
$REMOTE_REPO     = "git@github.com:patrioticcow/phalcon.git";
$DESIRED_BRANCH  = "dev";

echo shell_exec("cd {$LOCAL_ROOT} && git pull");

die("done " . mktime());