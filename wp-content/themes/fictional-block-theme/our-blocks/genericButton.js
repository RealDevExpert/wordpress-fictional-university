import { link } from "@wordpress/icons"
import { ToolbarGroup, ToolbarButton , Popover, Button, PanelBody, PanelRow, ColorPalette } from "@wordpress/components"
import { RichText, BlockControls, __experimentalLinkControl as LinkControl, InspectorControls, getColorObjectByColorValue } from "@wordpress/block-editor"
import { useState } from "@wordpress/element"

wp.blocks.registerBlockType("ourblocktheme/genericbutton", {
  title: "Generic Button",
  attributes: {
    text: {type: "string"},
    size: {type: "string", default: "large"},
    linkObject: {type: "object", default: {url: ""}},
    colorName: {type: "string", default: "blue"}
  },
  edit: EditComponent,
  save: SaveComponent
})

function EditComponent(props) {
  const [isLinkPickerVisible, setIsLinkPickerVisible] = useState(false)

  function handleTextChange(x) {
    props.setAttributes({text: x})
  }

  function buttonHandler() {
    setIsLinkPickerVisible(previousValue => !previousValue)
  }

  function handleLinkChange(newLink) {
    props.setAttributes({linkObject: newLink})
  }

  const ourColors = [
    {name: "blue", color: "#0d3b66"},
    {name: "orange", color: "#ee964b"},
    {name: "dark-orange", color: "#f95738"}
  ]

  const currentColorValue = ourColors.filter(color => {
    return color.name == props.attributes.colorName
  })[0].color

  function handleColorChange(colorCodeValue) {
    // from the hex value that the color palette gives us, we need to find its color name
    const { name } = getColorObjectByColorValue(ourColors, colorCodeValue)
    props.setAttributes({colorName: name})  
  }
  return (
    <>
      <BlockControls>
        <ToolbarGroup>
          <ToolbarButton onClick={buttonHandler} icon={link} />
        </ToolbarGroup>
        <ToolbarGroup>
          <ToolbarButton isPressed={props.attributes.size === "large"} onClick={() => props.setAttributes({size: "large"})}>Large</ToolbarButton>
          <ToolbarButton isPressed={props.attributes.size === "medium"} onClick={() => props.setAttributes({size: "medium"})}>Medium</ToolbarButton>
          <ToolbarButton isPressed={props.attributes.size === "small"} onClick={() => props.setAttributes({size: "small"})}>Small</ToolbarButton>
        </ToolbarGroup>
      </BlockControls>
      <InspectorControls>
        <PanelBody title="Color" initialOpen={true}>
          <PanelRow>Color</PanelRow>
          <ColorPalette colors={ourColors} value={currentColorValue} onChange={handleColorChange}/>
        </PanelBody>
      </InspectorControls>
      <RichText allowedFormats={[]} tagName="a" className={`btn btn--${props.attributes.size} btn--blue`} value={props.attributes.text} onChange={handleTextChange} />
      {isLinkPickerVisible && (
        <Popover position="middle center">
          <LinkControl settings={[]} value={props.attributes.linkObject} onChange={handleLinkChange} />
          <Button variant="primary" onClick={() => setIsLinkPickerVisible(false)} style={{display: "block", width: "100%"}}>Confirm Link</Button>
        </Popover>
      )}
    </>
  )
}

function SaveComponent(props) {
  return (
    <a href={props.attributes.linkObject.url} className={`btn btn--${props.attributes.size} btn--blue`}>
      {props.attributes.text}
    </a>
  )
}
