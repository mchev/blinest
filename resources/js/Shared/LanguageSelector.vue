<template>
    <div class="ml-4">
        <Link :href="route('language', [selectable_locale])">
            {{ selectable_locale }}
        </Link>
    </div>
</template>
 
<script>

    import { Link } from '@inertiajs/inertia-vue3'
    import Icon from '@/Shared/Icon'
 
    export default {
        components: {
            Link,
            Icon,
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