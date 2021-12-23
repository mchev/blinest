<template>
    <div class="ml-4">

        <dropdown class="mt-1 ml-auto" placement="bottom-end">
          <template #default>
            <div class="group flex items-center cursor-pointer select-none">
              <div class="mr-1 whitespace-nowrap">
                <span class="uppercase">{{ $page.props.locale }}</span>
              </div>
            </div>
          </template>
          <template #dropdown>
            <Link :href="route('language', [selectable_locale])" class="py-1 px-2 uppercase">
                {{ selectable_locale }}
            </Link>
          </template>
        </dropdown>

    </div>
</template>
 
<script>

    import { Link } from '@inertiajs/inertia-vue3'
    import Icon from '@/Shared/Icon'
    import Dropdown from '@/Shared/Dropdown'

    export default {
        components: {
            Link,
            Icon,
            Dropdown,
        },
     
        computed: {
            selectable_locale() {
                if(this.$page.props.locale == 'fr') {
                    return 'en';
                }
                return 'fr'
            }
        },
    }

</script>