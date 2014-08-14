<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 08/08/14
 * Time: 18:17
 */
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#blocktrackbar').trackbar({
            onMove: function() {
                document.getElementById('start-price').value = this.leftValue;
                document.getElementById('end-price').value = this.rightValue;
            },
            width: 160,
            leftLimit: 1000,
            leftValue: <?php
                if( isset( $_GET['start_price'])) {
                    if ((int)$_GET["start_price"] >= 1000 && (int)$_GET["start_price"] <= 50000) {
                        echo (int)$_GET["start_price"];
                    } else {
                        echo 1000;
                    }
                } else {
                    echo 1000;
                }
            ?>,
            rightLimit: 50000,
            rightValue: <?php
                if (isset( $_GET['end_price'])) {
                    if ((int)$_GET["end_price"] >= 1000 && (int)$_GET["end_price"] <= 50000) {
                        echo (int)$_GET["end_price"];
                    } else {
                        echo (int)$_GET['end_price'];
                    }
                } else {
                    echo 50000;
                }
            ?>,
            roundUp: 1000
        });
    });
</script>

<div id="block-parameter">
    <p class="header-title" >Поиск по параметрам</p>
    <p class="title-filter">Стоимость</p>
    <form method="GET" action="search_filter.php">
        <div id="block-input-price">
            <ul>
                <li><p>от</p></li>
                <li><input type="text" id="start-price" name="start_price" value="1000" /></li>
                <li><p>до</p></li>
                <li><input type="text" id="end-price" name="end_price" value="30000" /></li>
                <li><p>руб</p></li>
            </ul>
        </div>
        <div id="blocktrackbar"></div>
        <p class="title-filter">Производители</p>
        <ul class="checkbox-brand">
            <?php
            $sth = DB::getStatement("SELECT * FROM category WHERE type=?");
            $sth->execute(array('mobile'));
            $rows = $sth->fetchAll();
            foreach ($rows as $row):
                $checkedBrand = "";
                if ( isset($_GET['brand'])) {
                    if ( in_array( $row['id'], $_GET['brand'])) {
                        $checkedBrand = "checked";
                    }
                }
                ?>

                <li><input <?php echo $checkedBrand; ?> type="checkbox" name="brand[]" value="<?php echo $row['id']; ?>" id="<?php echo 'checkbrand'.$row['id']; ?>" /><label for="<?php echo 'checkbrand'.$row['id']; ?>"><?php echo $row['brand']; ?></label></li>

            <?php endforeach; ?>
        </ul>
        <input type="submit" id="button-param-search" name="submit" value=" " />
    </form>
</div>