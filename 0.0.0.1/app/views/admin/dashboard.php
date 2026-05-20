<?php

$title = "Табло за управление";
?>

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Общо потребители</p>
                    <h3 class="text-2xl font-bold mt-1">
                        <?= $stats['users_count'] ?? 124; ?>
                    </h3>
                </div>
                <div
                    class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-up text-xs"></i> 12%
                </span>
                <span class="ml-2 text-slate-400 text-xs">спрямо миналия месец</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Активни страници</p>
                    <h3 class="text-2xl font-bold mt-1">
                        <?= $stats['pages_count'] ?? 42; ?>
                    </h3>
                </div>
                <div
                    class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-file-lines text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-slate-400">
                <span>Последна промяна: преди 2 часа</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div
            class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-bold">Последни вписвания</h3>
                <a href="/admin/logs" class="text-sm text-indigo-600 hover:underline">Виж всички</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Потребител</th>
                            <th class="px-6 py-3 font-semibold">Действие</th>
                            <th class="px-6 py-3 font-semibold">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100 dark:divide-slate-700">
                        <tr>
                            <td class="px-6 py-4 font-medium italic">Admin</td>
                            <td class="px-6 py-4">Обнови начална страница</td>
                            <td class="px-6 py-4 text-slate-500">14:20 днес</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium italic">Krisi</td>
                            <td class="px-6 py-4">Създаде нов потребител</td>
                            <td class="px-6 py-4 text-slate-500">Вчера, 18:45</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="font-bold mb-4">Бързи действия</h3>
            <div class="grid grid-cols-1 gap-3">
                <button
                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Нова страница
                </button>
                <button
                    class="w-full py-2 px-4 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-gear text-xs"></i> Настройки на системата
                </button>
            </div>
        </div>
    </div>
</div>