<a href="/objects/<?=$objectId?>" class="w-full md:w-[350px] lg:w-[500px]">
    <div class="post border-2 rounded-md p-4">
        <div class="content flex space-x-2">
            <div class="left flex flex-col w-3/12 items-center">
                <?php if ($image != null) : ?>
                    <img src="/src/img/objects/<?=$objectId?>/<?=$image?>" class="h-[50px] lg:h-[70px] shadow-md mb-2">
                <?php else : ?>
                    <img src="/src/img/base_image.png" class="h-[50px] lg:h-[70px] shadow-md mb-2">
                <?php endif; ?>
                <p class="text-sm"><?=$classroom?></p>
            </div>
            <div class="right w-9/12">
                <div
                    class="
                        title
                        text-center
                        text-lg
                        font-bold
                        max-w-[20ch]
                        text-ellipsis
                        overflow-hidden
                        whitespace-nowrap
                    "
                >
                    <?=$title?>
                </div>
                <div class="description text-ellipsis max-w-[100ch] line-clamp-2 lg:line-clamp-3">
                    <?=$description?>
                </div>
            </div>
        </div>
    </div>
</a>