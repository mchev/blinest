<template>
    <div class="ml-4">

        <dropdown class="mt-1 ml-auto" placement="bottom-end">
          <template #default>
            <div class="group flex items-center cursor-pointer select-none">
              <div class="mr-1 whitespace-nowrap">
                <span class="uppercase"><Icon :name="theme" class="h-5 w-5 inline-block mr-2"/></span>
              </div>
            </div>
          </template>
          <template #dropdown>
			<button @click="theme = 'dark'" type="button" class="py-1 px-2 flex items-center cursor-pointer">
				<Icon name="dark" class="h-6 w-6 inline-block mr-2"/>
				Dark
            </button>
			<button @click="theme = 'light'" type="button" class="py-1 px-2 flex items-center cursor-pointer">
				<Icon name="light" class="h-6 w-6 inline-block mr-2"/>
                Light
            </button>
			<button @click="theme = 'system'" type="button" class="py-1 px-2 flex items-center cursor-pointer">
				<Icon name="system" class="h-6 w-6 inline-block mr-2"/>
                System
            </button>
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

		data() {
			return {
		      get theme() {
		        return localStorage.getItem('theme') || 'system';
		      },
		      set theme(value) {
		        localStorage.setItem('theme', value);
		      }
			}
		},

		mounted() {
			this.checkTheme(this.theme);
		},

        watch: {
            theme(newTheme, oldTheme) {
            	this.checkTheme(newTheme);
            }
        },

        methods: {

        	checkTheme(theme) {
				switch (theme) {
					case 'dark':
						localStorage.theme = 'dark';
						document.documentElement.classList.add('dark');
						break;
					case 'light':
						localStorage.theme = 'light';
						document.documentElement.classList.remove('dark')
						break;
					case 'system':
						localStorage.removeItem('theme');
						break;
				}
			}

        }
    }

</script>