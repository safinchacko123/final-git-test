<?php

if (stripos($page->content, "[qn]") !== false) {
    $admintags = array("[qn]", "[ans]", "[/qn]", "[/ans]");
    $htmltags = array('<dt class="active"><span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>', '<dd class="active"><i class="fa fa-hand-o-right"></i>', '</dt>', '</dd>');
    $finalhtml = str_ireplace($admintags, $htmltags, $page->content);
    echo $finalhtmlappnd = '<dl class="faqwrp">' . $finalhtml . '</dl>';
} else {
    echo $page->content;
}
?>
