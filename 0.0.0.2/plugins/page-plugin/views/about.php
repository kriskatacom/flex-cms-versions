<article class="max-w-4xl mx-auto py-10">
    <div class="flex flex-col md:flex-row items-center gap-10 mb-16">
        <div class="w-48 h-48 rounded-full bg-linear-to-tr from-blue-600 to-indigo-500 p-1 shadow-2xl">
            <div
                class="w-full h-full rounded-full bg-white dark:bg-slate-900 flex items-center justify-center overflow-hidden">
                <span class="text-6xl italic font-serif text-blue-600">K</span>
            </div>
        </div>

        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                <?php echo $author['name']; ?>
            </h1>
            <p class="text-xl text-blue-600 dark:text-blue-400 font-medium mt-2">
                <?php echo $author['role']; ?>
            </p>
            <p class="mt-6 text-slate-600 dark:text-slate-400 leading-relaxed text-lg italic">
                "
                <?php echo $author['bio']; ?>"
            </p>
            <div class="mt-6">
                <a href="<?php echo $author['website']; ?>" target="_blank"
                    class="font-semibold text-slate-900 dark:text-white hover:text-blue-600 transition-colors underline underline-offset-8 decoration-blue-500">
                    Виж портфолиото на kriskata.com →
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 border-t border-slate-200 dark:border-slate-800 pt-12">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Мисия на проекта</h2>
            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                <?php echo $history; ?>
            </p>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Експертиза</h2>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($author['skills'] as $skill): ?>
                    <span
                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-md text-sm font-semibold border border-slate-200 dark:border-slate-700">
                        <?php echo $skill; ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</article>