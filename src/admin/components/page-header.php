<?php
function getBreadCrumbLink($breadcrumb, $element)
{
    $link = '/admin/view/';
    foreach ($breadcrumb as $key => $value) {
        if ($value == 'Pages') {
            continue;
        }

        if ($value == $element) {
            $value = strtolower(str_replace('-', '', $value));
            $link .= $value . '/';
            break;
        }
        
        // Xoá - và chuyển thành chữ thường
        $value = strtolower(str_replace('-', '', $value));
        $link .= $value . '/';
    }
    return $link;
}
?>

<nav class="breadcrumb">
    <ol class="breadcrumb-list">
        <?php
        foreach ($breadcrumb as $key => $element) {
            if ($key == array_key_last($breadcrumb)) {
                echo '
                <li class="breadcrumb-item active" aria-current="page">
                    ' . $element . '
                </li>
            ';
            } else {
                echo '
            <li class="breadcrumb-item">
                <a class="breadcrumb-link" href="' . getBreadCrumbLink($breadcrumb, $element) . '">
                    ' . $element . '
                </a>
            </li>                            
        ';
            }
        }
        ?>
    </ol>
    <h1 class="page-header-title">
        <?php
        echo $pageHeader;
        ?>
    </h1>
</nav>