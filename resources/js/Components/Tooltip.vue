<template>
	<div class="group relative">
		<div class="invisible absolute bottom-7 right-0 mb-3 rounded-sm bg-white text-neutral-700 px-2 py-1 group-hover:visible">
			<div class="whitespace-nowrap text-xs">
				<slot name="tooltip" />
			</div>
			<svg class="absolute bottom-[-10px] right-[10px] z-10" width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M8 10L0 0L16 1.41326e-06L8 10Z" fill="white" />
			</svg>
		</div>
		<slot/>
	</div>
</template>
