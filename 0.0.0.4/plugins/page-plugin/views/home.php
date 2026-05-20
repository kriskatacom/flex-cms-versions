
<section
    class="text-center py-16 px-6 bg-slate-100 dark:bg-slate-800/50 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-inner">
    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
        <?php echo $hero_title; ?>
    </h1>
    <p class="mt-6 text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
        <?php echo $hero_text; ?>
    </p>
    <div class="mt-10">
        <a href="/page-contact"
            class="inline-block px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-blue-500/25">
            Започнете сега
        </a>
    </div>
</section>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
    <?php foreach ($cards as $card): ?>
        <div
            class="group p-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
            <div class="text-4xl mb-4 transform group-hover:scale-110 transition-transform">
                <?php echo $card['icon']; ?>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">
                <?php echo $card['title']; ?>
            </h3>
            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                <?php echo $card['desc']; ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>
