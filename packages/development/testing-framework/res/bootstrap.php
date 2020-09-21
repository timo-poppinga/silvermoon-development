<?php
$folder = __DIR__;
if(\strpos('packages/development/testing-framework/res', $folder) !== 0) {
     \chdir(\dirname($folder, 4));
}

