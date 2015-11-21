Peach Framework
===============
Фреймворк для создания сайтов в парадигме Single Page Application. Основной упор при разработке делается на проектирование гибкой, масштабируемой архитектуры. Разработка ведется с учетом опыта работы с другими фреймворками, в том числе и самописными. Архитектура проектируется с целью предоставления возможности построить на фреймворке масштабируемые приложения любой сложности. Фреймворк уже содержит построенную архитектуру клиентской и серверной части. Для сборки фронтенда используется gulp. Настроена компиляция js через babel, компиляция css через stylus, минификация и склейка css и js файлов.

Требования
==============
- PHP 5.5    
- Node.js 4.0    
- NPM 2.4.12   

Установка
==============
1. Скопировать файлы проекта в директорию хоста. В директории хоста не должно быть директории www - она содержится в самом фреймворке, т.е. файлы фреймворка кроме статики и точки входа лежат на уровень выше чем root хоста.
2. Необходимо установить сборку статики 
 * install gulp -g 
 * cd static_builder 
 * npm install
3. Для запуска тестов необходимо установить PHPUnit глобально.
4. Для просмотра статистики покрытия тестами необходимо установить Xdebug

Протестировано на Ubuntu 14.04 LTS на LAMP стэке.
