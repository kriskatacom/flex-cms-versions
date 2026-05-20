<?php

namespace Flex\Core\Events;

/**
 * EventManager - Нервната система на Flex CMS.
 * Управлява "Actions" (за изпълнение на код) и "Filters" (за промяна на данни).
 */
class EventManager
{
    private static $instance = null;
    protected $actions = [];
    protected $filters = [];

    // Singleton модел, за да имаме единна точка на достъп
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // --- ACTIONS (СЪБИТИЯ) ---

    /**
     * Плъгинът се "закача" за събитие.
     */
    public function listen(string $hook, callable $callback, int $priority = 10): void
    {
        $this->actions[$hook][$priority][] = $callback;
    }

    /**
     * Ядрото задейства събитието.
     */
    public function trigger(string $hook, ...$args): void
    {
        if (!isset($this->actions[$hook]))
            return;

        // Сортиране по приоритет (напр. 1 се изпълнява преди 10)
        ksort($this->actions[$hook]);

        foreach ($this->actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }

    // --- FILTERS (ФИЛТРИ) ---

    /**
     * Плъгинът добавя филтър за промяна на променлива.
     */
    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][$priority][] = $callback;
    }

    /**
     * Ядрото прилага филтрите върху стойност.
     */
    public function applyFilters(string $hook, $value, ...$args)
    {
        if (!isset($this->filters[$hook]))
            return $value;

        ksort($this->filters[$hook]);

        foreach ($this->filters[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                // Филтърът приема стойността, променя я и я връща
                $value = call_user_func($callback, $value, ...$args);
            }
        }

        return $value;
    }
}
