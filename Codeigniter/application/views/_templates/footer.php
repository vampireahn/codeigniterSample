<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

</main>

<footer class="container mt-5 py-4 border-top">
	<p>&copy; <?php echo date('Y'); ?> P2P Project. All Rights Reserved.</p>
</footer>

<?php // CSRF 토큰 이름을 JavaScript 전역 변수로 전달합니다. ?>
<script>
	const csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?php // 공통 JavaScript 파일을 로드합니다. ?>
<script src="<?php echo base_url('assets/js/common.js'); ?>"></script>

</body>
</html>
