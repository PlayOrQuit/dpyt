import React from 'react';
import { WithContext as ReactTags } from 'react-tag-input';
import PropTypes from 'prop-types';
import {Form} from 'react-bootstrap';
const KeyCodes = {
    comma: 188,
    enter: 13,
};
const delimiters = [KeyCodes.comma, KeyCodes.enter];
class TagsGroupReact extends React.Component{
    constructor(props){
        super(props);
    }
    static propTypes = {
        label: "",
        placeholder: "",
        error: null,
    }
    handleDelete = (i) => {
        let newTags = [...this.props.tags];
        newTags = newTags.filter((tag, index) => index !== i)
        this.props.onChange(newTags);
    }

    handleAddition = (tag) => {
        const newTags = [...this.props.tags];
        newTags.push(tag);
        this.props.onChange(newTags);
    }

    handleDrag = (tag, currPos, newPos) => {
        const tags = [...this.props.tags];
        const newTags = tags.slice();
        newTags.splice(currPos, 1);
        newTags.splice(newPos, 0, tag);
        this.props.onChange(newTags);
    }
    render() {
        const {tags, label, placeholder, error} = this.props;
        return(
            <Form.Group>
                {label ? <Form.Label>{label}</Form.Label> : ""}
                <ReactTags
                    placeholder={placeholder}
                    tags={tags}
                    delimiters={delimiters}
                    handleDelete={this.handleDelete}
                    handleAddition={this.handleAddition}
                    handleDrag={this.handleDrag}
                    classNames={{
                        tagInput: '',
                        tagInputField: 'form-control',
                    }}

                />
                {error ? <div className="invalid-feedback" style={{display: "block"}}>{error}</div> : ""}
            </Form.Group>
        );
    }
}
TagsGroupReact.propTypes = {
    tags: PropTypes.array.isRequired,
    label: PropTypes.string,
    placeholder: PropTypes.string,
    error: PropTypes.string,
    onChange: PropTypes.func.isRequired
};
export default TagsGroupReact;