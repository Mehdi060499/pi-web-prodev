import { preprocessMeltUI, sequence } from '@melt-ui/pp'
 
const config = {
  // ... other svelte config options
  preprocess: sequence([
    // ... other preprocessors
    preprocessMeltUI() // add to the end!
  ])
}
 
export default config