<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>
<?php 
if ( defined('G5_DEBUG') && G5_DEBUG ){
?>
<!-- 
RUN TIME : <?php echo get_microtime()-$begin_time; ?>

<?php print_r( get_included_files() ); ?> 

<?php print_r( $g5_debug['sql'] ); ?>

<?php print_r( $GLOBALS ); ?>
-->
<?php
}
?>
