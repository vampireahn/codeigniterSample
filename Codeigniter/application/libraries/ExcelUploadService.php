<?php
defined('BASEPATH') or exit('No direct script access allowed');
	
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	/**
 * Class ExcelUploadService
 *
 * 엑셀 파일을 읽어 데이터를 배열로 변환하는 공통 서비스입니다.
 */
class ExcelUploadService
{
	/**
	 * 엑셀 파일 경로를 받아 데이터를 배열로 반환합니다.
	 *
	 * @param string $filePath - 서버에 저장된 엑셀 파일의 전체 경로
	 * @return array|null - 성공 시 데이터 배열, 실패 시 null
	 */
	public function readData($filePath)
	{
		try {
			// 1. 파일 타입 자동 감지 및 스프레드시트 객체 생성
			$spreadsheet = IOFactory::load($filePath);

			// 2. 활성 시트의 데이터를 배열로 변환
			// 첫 번째 행(헤더)도 데이터에 포함시키기 위해 네 번째 인자를 false로 설정합니다.
			// 두 번째 인자 true는 수식의 결과 값을 가져오도록 합니다.
			$dataArray = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

			return $dataArray;
		} catch (Exception $e) {
			// 파일 읽기 실패 시 로그를 남기고 null 반환
			log_message('error', 'Excel file reading error: ' . $e->getMessage());
			return null;
		}
	}
}
