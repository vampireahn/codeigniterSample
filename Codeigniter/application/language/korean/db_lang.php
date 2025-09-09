<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = '제출된 연결 문자열에 기반하여 데이터베이스 설정을 결정할 수 없습니다.';
$lang['db_unable_to_connect'] = '제공된 설정으로 데이터베이스 서버에 연결할 수 없습니다.';
$lang['db_unable_to_select'] = '지정된 데이터베이스를 선택할 수 없습니다: %s';
$lang['db_unable_to_create'] = '지정된 데이터베이스를 생성할 수 없습니다: %s';
$lang['db_invalid_query'] = '제출된 쿼리가 유효하지 않습니다.';
$lang['db_must_set_table'] = '쿼리와 함께 사용할 데이터베이스 테이블을 설정해야 합니다.';
$lang['db_must_use_set'] = '항목을 업데이트하려면 "set" 메소드를 사용해야 합니다.';
$lang['db_must_use_index'] = '일괄 업데이트를 위해서는 일치시킬 인덱스를 지정해야 합니다.';
$lang['db_batch_missing_index'] = '일괄 업데이트를 위해 제출된 하나 이상의 행에 지정된 인덱스가 없습니다.';
$lang['db_must_use_where'] = '"where" 절이 포함되지 않은 업데이트는 허용되지 않습니다.';
$lang['db_del_must_use_where'] = '"where" 또는 "like" 절이 포함되지 않은 삭제는 허용되지 않습니다.';
$lang['db_field_param_missing'] = '필드를 가져오려면 테이블 이름을 매개변수로 지정해야 합니다.';
$lang['db_unsupported_function'] = '사용 중인 데이터베이스에서는 이 기능을 사용할 수 없습니다.';
$lang['db_transaction_failure'] = '트랜잭션 실패: 롤백이 수행되었습니다.';
$lang['db_unable_to_drop'] = '지정된 데이터베이스를 삭제할 수 없습니다.';
$lang['db_unsupported_feature'] = '사용 중인 데이터베이스 플랫폼에서 지원하지 않는 기능입니다.';
$lang['db_unsupported_compression'] = '선택한 파일 압축 형식은 서버에서 지원하지 않습니다.';
$lang['db_filepath_error'] = '제출한 파일 경로에 데이터를 쓸 수 없습니다.';
$lang['db_invalid_cache_path'] = '제출한 캐시 경로가 유효하지 않거나 쓰기 가능하지 않습니다.';
$lang['db_table_name_required'] = '해당 작업에는 테이블 이름이 필요합니다.';
$lang['db_column_name_required'] = '해당 작업에는 컬럼 이름이 필요합니다.';
$lang['db_column_definition_required'] = '해당 작업에는 컬럼 정의가 필요합니다.';
$lang['db_unable_to_set_charset'] = '클라이언트 연결 문자 집합을 설정할 수 없습니다: %s';
$lang['db_error_heading'] = '데이터베이스 오류가 발생했습니다';