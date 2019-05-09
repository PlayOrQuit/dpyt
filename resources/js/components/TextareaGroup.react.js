import React from 'react';
import {Form} from 'react-bootstrap';
import PropTypes from 'prop-types';

class TextareaGroup extends React.Component{
    constructor(props){
        super(props);
    }
    static propTypes = {
        value: "",
        label: "",
        placeholder: "",
        title: "",
        error: null,
        rows: 3
    }
    render() {
        const {label, rows, name, title, placeholder, onChange, error, value} = this.props;
        return(
            <Form.Group>
                {label ? <Form.Label>{label}</Form.Label> : ""}
                <Form.Control
                    value={value}
                    as="textarea"
                    rows={rows}
                    name={name}
                    title={title}
                    placeholder={placeholder}
                    onChange={onChange}
                />
                {error ? <div className="invalid-feedback" style={{display: 'block'}}>{error}</div> : ""}

            </Form.Group>
        );
    }
}

TextareaGroup.propTypes = {
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number,
        PropTypes.bool
    ]),
    label: PropTypes.string,
    placeholder: PropTypes.string,
    title: PropTypes.string,
    name: PropTypes.string.isRequired,
    rows: PropTypes.number,
    error: PropTypes.string,
    onChange: PropTypes.func.isRequired
};

export default TextareaGroup;