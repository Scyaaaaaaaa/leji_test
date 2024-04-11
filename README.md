API文件

	概述 : 找出建案類型為大樓且管理員部門為資料部的建案，取得各別建案最高交易單價

	Url : /building-case/highest-price

	method :  GET

	request : null

	response : json格式

		"status":"000000",
		"message":"Success",
		"items":{
			{
				"building_case_id":"1",
				"building_case_name":"建案名稱",
				"highest_trading_price":"10000",
				"building_case_type":"大樓",
				"manager_department":"資料部",
			},
			{
				"building_case_id":"2",
				"building_case_name":"建案名稱2",
				"highest_trading_price":"20000",
				"building_case_type":"大樓",
				"manager_department":"資料部",
			},
		}


	items回傳資料:
	building_case_id:建案id
	building_case_name:建案名稱
	highest_trading_price:建案最高成交價
	building_case_type:建案類型
	manager_department:管理員種類
	
資料庫文件:

	managers:  //管理員
		id:bigint unsigned
		department:varchar(128) //部門
		name:varchar(64) //管理員名稱
		created_at:timestamp
		updated_at:timestamp
		index('department')//常用於查詢
		
	building_cases://建案
		id:bigint unsigned
		type:varchar(64)
		manager_id:bigint unsigned  //管理員id 關聯到managers
		name:varchar(128) //建案名稱
		created_at:timestamp
		updated_at:timestamp
		index('manager_id', ''type') //manager_id為外來鍵 type常用於查詢
	
	building_case_tragings://建案交易紀錄
		id:bigint unsigned
		case_id:bigint unsigned  //建案id 關聯到building_cases
		price:bigint unsigned //成交價格
		created_at:timestamp
		updated_at:timestamp
		index('case_id', 'price') //case_id為外來鍵 price常用於查詢
	
	highest_price_records: //建案最高成交價格紀錄
		id: bigint unsigned
		case_id:bigint unsigned //建案id
		trading_id:bigint unsigned //建案交易紀錄id
		case_type:varchar(64) //建案類型
		case_name:varchar(128) //建案名稱
		manager_name:varchar(64) //管理員名稱
		manager_department:varchar(128) //部門
		highest_price:bigint unsigned //最高成交價格
		created_at:timestamp
		updated_at:timestamp
		

	API優化:
	在查詢是我撈出的資料在controller內還有做處理，如果是可行的話希望可以在撈資料時就是我需要的資料了
	不用再多做其他處理，在BuildingCaseRepository中getEachCaseMostPrice中有嘗試過但是找不到解法



	排程部分及優化:
	因為我用的是laravel 11 所以是用新的task schedule 在route/console.php內設定每天中午12點執行。
	優化可能會想把存取最高各個建案成交價寫成到service，再在command引用
		
		
		
