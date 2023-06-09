<?php
require 'DBConnect.php';// подключаем файл соединения с бд

class News
{
	/**
	 * метод для получения списка новостей
	 * если передан лимит, выбирается ограниченное кол-во новостей
	 * если лимит не передан, выбираются все новости из таблицы
	 */
	public static function getLimitNewsList($limit = 0){
		$pdo = DBConnect::getConnection(); // подключаемся к бд

		// если нет лимита, отображаем все новости
		$tail = '';
		if($limit === 0){
			$tail = ";";
		}else{
			$tail = "LIMIT :limit;";
		}

		$query = "SELECT news.id AS news_id, news.title, add_date, text, image, 
				authors.id AS authors_id, first_name, last_name, avatar,
				translation, class_name
				FROM `news`, authors, category 
				WHERE authors.id = author_id
				AND category.id = category_id
				ORDER BY add_date DESC " . $tail;

		if($limit === 0){
			$result = $pdo->query($query);
		}else{
			$result = $pdo->prepare($query);
			$result->bindValue(':limit', $limit, PDO::PARAM_INT);
			$result->execute();
		}

		return $result->fetchAll();
	}


	/**
	 * метод для получения данных об одной новости по ID
	 */
	public static function getNewsItemById($id){
		$pdo = DBConnect::getConnection(); // подключаемся к бд

		//	news: id, title, text, add_date, image
		//  authors: id, first_name, last_name, short_info, avatar
		//  category: id, translation, class_name
		$query = "SELECT news.id AS news_id, news.title, text, add_date, image,
							authors.id AS authorId, first_name, last_name, short_info, avatar,
							category.id AS categoryId, translation, class_name
							FROM news, authors, category
							WHERE author_id = authors.id 
							    AND category_id = category.id
									AND news.id = $id;";
	}

}