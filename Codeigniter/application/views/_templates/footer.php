<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php // CSRF 토큰 이름을 JavaScript 전역 변수로 전달합니다. ?>
<script>
	const csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
</script>

<?php // 공통 JavaScript 파일을 로드합니다. ?>
<script src="<?php echo base_url('assets/js/common.js'); ?>"></script>

</body>
</html>
