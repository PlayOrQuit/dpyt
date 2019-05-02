import React from 'react';
import {Form} from 'react-bootstrap';
import PropTypes from 'prop-types';

class InputGroupReact extends React.Component{
    constructor(props){
        super(props);
    }
    static propTypes = {
        value: "",
        label: "",
        placeholder: "",
        title: "",
        error: null
    }
    render() {
        const {label, type, name, title, placeholder, onChange, error, value} = this.props;
        return(
            <Form.Group>
                {label ? <Form.Label>{label}</Form.Label> : ""}
                <Form.Control
                    value={value}
                    type={type}
                    name={name}
                    title={title}
                    placeholder={placeholder}
                    onChange={onChange}
                    className={error ? "is-invalid" : ""}
                />
                {error ? <div className="invalid-feedback">{error}</div> : ""}

            </Form.Group>
        );
    }
}

InputGroupReact.propTypes = {
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number,
        PropTypes.bool
    ]),
    label: PropTypes.string,
    placeholder: PropTypes.string,
    title: PropTypes.string,
    name: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
    error: PropTypes.string,
    onChange: PropTypes.func.isRequired
};

export default InputGroupReact;