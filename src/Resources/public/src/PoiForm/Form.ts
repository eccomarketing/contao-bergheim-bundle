import Quill from "quill"
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";

export default class Form
{
    public editors: Quill[] = []

    constructor(public form: HTMLFormElement)
    {
        this.form.name = 'poiForm'
        this.form.addEventListener('submit', (e: SubmitEvent) => this.onSubmit(e), false)

        this.registerSubmitOnChange();
        this.initEditors()
    }

    private registerSubmitOnChange(): void
    {
        this.form.querySelectorAll('select.submit').forEach((select: HTMLSelectElement) => {
            select.addEventListener('change', () => {this.form.submit()})
        })
    }

    private onSubmit(event: SubmitEvent): void
    {
        for(const collection of this.editors)
        {
            const editor = collection.editor
            const textarea = collection.textarea
            const required = collection.required

            // Set new content
            textarea.value = editor.isEmpty() ? '' : editor.getHTML()

            // Check required fields
            if(required && !textarea.value.trim())
            {
                event.preventDefault()

                textarea.required = true
                textarea.reportValidity()

                setTimeout(() => {
                    textarea.required = false
                }, 1000)

                break
            }
        }
    }

    private initEditors(): void
    {
        document.querySelectorAll('textarea.editor').forEach((textarea: HTMLTextAreaElement, index) => {
            // Create container dom
            const hidden = document.createElement('div')
                  hidden.style.height = '0'
                  hidden.style.height = '0'
                  hidden.style.overflow = 'hidden'

            // Create container dom
            const container = document.createElement('div')
                  container.style.height = '200px'
                  container.innerHTML = textarea.value

            // Remove required
            const required = textarea.required
            textarea.required = false

            // Insert editor after textarea
            textarea.after(container)

            // Insert hidden before textarea
            textarea.before(hidden)

            // Hide textarea
            hidden.append(textarea)

            // Initialise editor
            const editor = new Quill(container, {
                theme: 'snow',
                modules: {
                    toolbar: ['bold', 'italic', 'underline', 'strike']
                }
            })

            editor.getHTML = function() {
                return this.container.querySelector('.ql-editor').innerHTML;
            }

            editor.isEmpty = function() {
                if ((this.getContents()['ops'] || []).length !== 1) { return false }
                return this.getText().trim().length === 0
            }

            this.editors.push({
                container,
                editor,
                textarea,
                required
            })
        })
    }
}
