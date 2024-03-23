1. ev_mint_install.sh z brancha mint4
2. wyłącz ajax w config.php: 'disableAjaxUI' => true
3. utwórz folder legacy i przerzuć do niego wszystkie pliki i foldery z instancji
4. przekopiuj folder api z brancha mint4 do roota instancji
5. w api/app/Config/AppConfig.php ustaw ścieżkę do swojej instancji
6. legacy/.htaccess: edytuj RewriteBase na /nazwa_instancji/legacy
7. do roota instancji przekopiuj .htaccess z repo: vue/.htaccess i podmień RewriteBase
8. podepnij elastica pod instancję - wersja 5.6. na przykład elastic56.int2.evolpe.net:9203
9. podobnie ogarnij elastica w api/configs/mint/config.php. Najlepiej utwórz config_override.php i tam to edytuj
10. z repo przekopiuj zawartość vue/dist do roota instancji
11. ostatecznie instancja powinna mieć strukturę:
* /api
* /assets
* /legacy
* .htaccess
* index.html
* no i jakieś publiczne pliki z dist, typu favicon.ico albo bg.jpg