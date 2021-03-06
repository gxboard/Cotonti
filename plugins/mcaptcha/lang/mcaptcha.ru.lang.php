<?php
/**
 * Russian langfile for mcaptcha
 *
 * @package mcaptcha
 * @author Cotonti Team
 */

defined('COT_CODE') or die('Wrong URL');

$L['info_desc'] = 'Математическая CAPTCHA. Защищает ваш сайт от спам-ботов с помощью простых арифметических задач. Требует JavaScript.';

$L['mcaptcha_error'] = 'Ошибка: слишком много попыток, попробуйте позже.';
$L['captcha_verification_failed'] = 'Пример решен неверно.';

$L['cfg_delay'] = array('Задержка против хаммеринга', 'сек.');
$L['cfg_attempts'] = array('Макс. число попыток в секунду', '0 - не ограничено');
?>
