<?php
    echo isset($meta['dt']) ? date('Y-m-d', strtotime($meta['dt'])) : date('Y-m-d');
?>