Url's for 1c-import:
 - /index.php?route=module/1c_import/process
 - /index.php?route=module/1c_import/process&noSeoUrls=false - без генерации СЕО УРЛОВ

Соответствие полей импорта полям БД opencart (повторно обновляются цены и наличие):
Product:
 - Код - product_to_1c.1c_product_id
 - Наименование - product_description.name
 - Модель - product.model
 - Фирма - manufacturer_description.name
 - ФирмаКод - manufacturer.manufacturer_id
 - ШтрихКод - product.ean
 - КодГруппы - category.category_id
 - Наличие - product.stock_status_id
 - КаталНомер - product.sku
 - КаталНомерНаш - product.isbn (все равно не используется)
 - Применяемость - product_description.description
 - Комментарий - product.comment
 - Цены - обрабатываеются как product_special для разных customer_group
 - Характеристики - обрабатываются как аттрибуты
 - КВЭД - не обрабатывается
 - ИмяГруппы - не обрабатывается
 - ПолноеНаименование - не обрабатывается
 - Гарантия - не обрабатывается



