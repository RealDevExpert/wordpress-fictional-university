import { RichText} from "@wordpress/block-editor"

wp.blocks.registerBlockType("ourblocktheme/genericheading", {
  title: "Generic Heading",
  attributes: {
    text: {type: "string"},
    size: {type: "string", default: "large"}
  },
  edit: EditComponent,
  save: SaveComponent
})

function EditComponent(props) {
  function handleTextChange(x) {
    props.setAttributes({text: x})
  }

  return (
    <>
      <RichText allowedFormats={["core/bold"]} tagName="h1" className={`headline headline--${props.attributes.size}`} value={props.attributes.text} onChange={handleTextChange} />
    </>
  )
}

function SaveComponent(params) {
  return (
   <div>This is our heading.</div>
  )
}