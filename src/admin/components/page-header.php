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
                        <a class="breadcrumb-link" href="javascript:;">
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