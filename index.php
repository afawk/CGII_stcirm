<!DOCTYPE html>
<html>
<body>
<section>
    <form>
        <select name="i" onchange="this.form.submit();">
        <?php for($i = 1, $sel = ''; $i <= 10; $i++):
            $sel = ($_GET['i'] == $i ? 'selected="selected"' : '');
        ?>
            <option <?=$sel?> value="<?=str_pad($i, 2, '0', STR_PAD_LEFT)?>">Imagem <?=$i?></option>
        <?php endfor; ?>
        </select>
    </form>
    <br>
</section>
<?php if (isset($_GET['i']) and is_numeric($_GET['i'])): ?>
    <div>
        <img style="width:512px;height:512px;" src="src/resources/imagens/gt_fatia<?=$_GET['i']?>.bmp" />
        <img style="width:512px;height:512px;" src="image.php?i=<?=$_GET['i']?>&type=rgb" />
        <img style="width:512px;height:512px;" src="image.php?i=<?=$_GET['i']?>&type=grey" />
        <br>
        <img style="width:512px;height:512px;" src="image.php?i=<?=$_GET['i']?>&type=r" />
        <img style="width:512px;height:512px;" src="image.php?i=<?=$_GET['i']?>&type=g" />
        <img style="width:512px;height:512px;" src="image.php?i=<?=$_GET['i']?>&type=b" />
        <br>
        <img src="image.php?i=<?=$_GET['i']?>&type=hist" />
    </div>
<?php endif; ?>
</body>
</html>