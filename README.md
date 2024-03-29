# Yii2 Web

В компонент вынесен основной контроллер для веб приложения со стандартизированными фильтрами и поведениями, CRUD действия,
а также наиболее частые действия, такие как переключение активности сущности или измения статуса.

В контроллер для веба компонента вынесена базовая настройка поведения по фильтрации доступа к действиям.

## Установка

Чтобы установить компонент, необходимо добавить следующие строки в файл `composer.json`:
```
"require": {
    "oleg-chulakov-studio/yii2-web": "~1.0.0"
}
```

Или выполнить команду:

```
composer require oleg-chulakov-studio/yii2-web
```

## Контроль доступа

По умолчанию в Yii2 используется два поведения, которые контролируют доступ к
каждому контролеру. Настройка `VerbFilter` и `AccessControl` фильтров доступа занимает
достаточно весомый массив правил. Поэтому данная настройка была упрощена путем
создания базового массива доступа:

```
public function accessRules()
{
    return [
        'index'   => $this->createAccess('get', true),
        'view'    => $this->createAccess('get', true, '@'),
        'create'  => $this->createAccess('post', true, '@'),
        'update'  => $this->createAccess('put, patch', true, '@'),
        'delete'  => $this->createAccess('delete', true, '@'),
        'options' => $this->createAccess(),
    ];
}
```

Элементы метода доступа и правил доступа может быть записан в двух вариациях:
```php
...
'update'  => $this->createAccess('put, patch', true, ['admin', '@']),
...
```

- `'post, get'` - методы доступа к экшену, строка с элементами, разделенными запятыми
- `['admin', '@']` - Правило разрешения доступа к экшену, массив элементов

Если требуется расширенная настройка поведения, отличающаяся от стандартного,
можно переопределить методы генерации конфигурации фильтра:

**Настройка для фильтра AccessControl**

За создание настроек для фильтра по уровню доступа `AccessControl` отвечает метод `accessBehavior`
получающий список правил доступа `$rules` и возвращающий конфигурацию поведения.

**Настройка фильтра VerbFilter**

Создание настроек для фильтра доступа по методу обращения к экшену `VerbFilter` выполняет метод `verbsBehavior`, получающий список
`$actions` с методами доступа к ним и возвращающий конфигурацию поведения.
