<?php
class UniversalModel {
    /** @var array Метаданные для полей модели. */
    private $fieldMetadata;

    /** @var array Ассоциативный массив для хранения значений полей. */
    private $data = [];

    /**
     * Конструктор класса.
     *
     * @param array $fieldMetadata Метаданные для полей модели.
     */
    public function __construct(array $fieldMetadata) {
        $this->fieldMetadata = $fieldMetadata;
    }

    /**
     * Устанавливает значение поля модели.
     *
     * @param string $fieldName Имя поля.
     * @param mixed $value Значение поля.
     *
     * @throws InvalidArgumentException Если поле не существует в метаданных.
     */
    public function setField($fieldName, $value) {
        if (!isset($this->fieldMetadata[$fieldName])) {
            throw new InvalidArgumentException("Поле '$fieldName' не существует в метаданных.");
        }

        $this->data[$fieldName] = $value;
    }

    /**
     * Получает значение поля модели.
     *
     * @param string $fieldName Имя поля.
     *
     * @return mixed Значение поля.
     *
     * @throws InvalidArgumentException Если поле не существует в метаданных.
     */
    public function getField($fieldName) {
        if (!isset($this->fieldMetadata[$fieldName])) {
            throw new InvalidArgumentException("Поле '$fieldName' не существует в метаданных.");
        }

        return $this->data[$fieldName] ?? null;
    }

    public function getData() {
        return $this->data;
    }

    /**
     * Валидация обязательных полей модели.
     *
     * @throws InvalidArgumentException Если какое-либо обязательное поле отсутствует.
     */
    public function validate() {
        foreach ($this->fieldMetadata as $fieldName => $fieldData) {
            if ($fieldData['required'] && empty($this->data[$fieldName])) {
                throw new InvalidArgumentException("Обязательное поле '$fieldName' отсутствует.");
            }
        }
    }
}

?>