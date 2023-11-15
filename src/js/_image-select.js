const imageSelect = () => {
	const initialiseInput = () => {

		const buttonUploadSelector = '.image-upload'
		const linkRemoveSelector = '.image-remove'
		const imagePreviewSelector = '.image-preview'
		const textInputSelector = '.meta-image'

		const buttonUpload = document.querySelector( buttonUploadSelector )
		const linkRemove = document.querySelector( linkRemoveSelector )
		if ( ! buttonUpload ) return

		let mediaFrame

		const removeAllChildren = ( element ) => {
			while ( element.firstChild ) {
				element.removeChild( element.lastChild )
			}
		}

		buttonUpload.addEventListener( 'click', async ( e ) => {
			e.preventDefault()
			const imagePreview = buttonUpload.parentElement.parentElement.querySelector( imagePreviewSelector )
			const textInput = buttonUpload.parentElement.querySelector( textInputSelector )

			// If the frame already exists, re-open it.
			if ( mediaFrame ) {
				mediaFrame.open()
				return
			}

			// Create a new media frame.
			mediaFrame = wp.media( {
				title: 'Select media to use as the profile image',
				button: {
					text: 'Use this image',
				},
				multiple: false,
			} )

			mediaFrame.on( 'select', () => {
				// Get attachment selection and create a JSON representation of the model.
				const attachment = mediaFrame.state().get( 'selection' ).first().toJSON()
				const domain = window.location.origin
				const relativePath = attachment.url.replace( domain, '' )

				textInput.value = attachment.id
				const imageElement = imagePreview.querySelector( 'img' )
				if ( imageElement ) {
					imagePreview.querySelector( 'img' ).setAttribute( 'src', relativePath )
				} else {
					const newImg = document.createElement( 'img' )
					newImg.setAttribute( 'src', relativePath )
					newImg.setAttribute( 'width', '100px' )
					newImg.setAttribute( 'height', '100px' )
					removeAllChildren( imagePreview )
					imagePreview.appendChild( newImg )
					linkRemove.style.display = 'block'
				}
			} )

			// Opens the media library frame.
			mediaFrame.open()
		} )

		linkRemove.addEventListener( 'click', async ( e ) => {
			e.preventDefault()
			const imagePreview = linkRemove.parentElement.parentElement.querySelector( imagePreviewSelector )
			const textInput = linkRemove.parentElement.querySelector( textInputSelector )
			removeAllChildren( imagePreview )
			textInput.value = ''
			linkRemove.style.display = 'none'
		} )
	}

	const docLoaded = setInterval( () => {
		if ( document.readyState === 'complete' ) {
			clearInterval( docLoaded )
			initialiseInput()
		}
	}, 100 )
}

export { imageSelect }
