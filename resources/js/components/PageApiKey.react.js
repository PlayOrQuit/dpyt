import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {
    Container,
    Col,
    Row,
    Alert,
    Button
} from 'react-bootstrap';
import _ from 'lodash';
import ReactTable from 'react-table';
import CardLoaderReact from './CardLoader.react';
import InputGroupReact from './InputGroup.react';
import Pagination from './Pagination.react';
import Icon from './Icon.react';
import {
    URL_API_KEY_CREATE,
    URL_API_KEY_GET,
    URL_API_KEY_DELETE,
    URL_API_KEY_EDIT_PRIMARY,
    STATUS_CODE_OK,
    STATUS_CODE_FIELD_ERROR,
    STATUS_CODE_DB_ERROR,
    TIME_OUT_REQUEST
} from '../util/constant';
import {fetch} from '../util/util';
import trans from '../lang/index';
import 'react-table/react-table.css'

class PageApiKeyReact extends Component {
    constructor(props) {
        super(props);
        this.state = {
            api_key: '',
            err_api_key: null,
            id_client: '',
            err_id_client: null,
            client_secret: '',
            err_client_secret: null,
            isLoading: false,
            loadMore: false,
            keys: [],
            message: null,
            messageType: 'info',
            deletes: []
        }
    }

    componentDidMount() {
        this.getData();
    }

    getData = () => {
        this.updateState('loadMore', true);
        fetch(URL_API_KEY_GET, 'get', {})
            .then(result => {
                setTimeout(() => {
                    this.updateState('loadMore', false);
                    if (result.data.body.statusCode === STATUS_CODE_OK) {
                        this.updateState('keys', result.data.body.data);
                    }
                }, TIME_OUT_REQUEST);
            })
            .catch(error => {
                console.log(error);
                setTimeout(() => {
                    this.updateState('loadMore', false);
                }, TIME_OUT_REQUEST);
            });
    }

    showAlert = (message, type) => {
        this.updateState('message', message);
        this.updateState('messageType', type);
    }
    hideAlert = () => {
        this.showAlert(null, 'info');
    }
    updateState = (k, v) => {
        const newState = this.state;
        newState[k] = v;
        this.setState(newState);
    }
    setError = (k, msg) => {
        if (k === 'api_key') {
            this.updateState('err_api_key', msg);
        } else if (k === 'id_client') {
            this.updateState('err_id_client', msg);
        } else if (k === 'client_secret') {
            this.updateState('err_client_secret', msg);
        }
    }
    handlerChangeApiKey = (v) => {
        if (v === '') {
            this.setError('api_key', trans.get('validation.required', {attribute: trans.get('keyword.key')}));
        } else {
            this.updateState('api_key', v);
            this.setError('api_key', null);
        }

    }
    handlerChangeIdClient = (v) => {
        if (v === '') {
            this.setError('id_client', trans.get('validation.required', {attribute: trans.get('keyword.id_client')}));
        } else {
            this.updateState('id_client', v);
            this.setError('id_client', null);
        }
    }
    handlerChangeClientSecret = (v) => {
        if (v === '') {
            this.setError('client_secret', trans.get('validation.required', {attribute: trans.get('keyword.client_secret')}));
        } else {
            this.updateState('client_secret', v);
            this.setError('client_secret', null);
        }
    }
    submitResult = (data) => {
        if (data.statusCode === STATUS_CODE_OK) {
            let keyNews = this.state.keys;
            keyNews.push(data.data);
            this.updateState('keys', keyNews);
            this.showAlert(data.message, 'success');
        } else if (data.statusCode === STATUS_CODE_FIELD_ERROR) {
            if (data.field_errors.api_key) {
                this.setError('api_key', data.field_errors.api_key[0]);
            } else if (data.field_errors.id_client) {
                this.setError('id_client', data.field_errors.id_client[0]);
            } else if (data.field_errors.client_secret) {
                this.setError('client_secret', data.field_errors.client_secret[0]);
            }
        } else if (data.statusCode === STATUS_CODE_DB_ERROR) {
            this.showAlert(data.message, 'danger');
        } else {
            this.showAlert(data.message, 'danger');
        }
    }
    submitData = () => {
        if (this.state.api_key === '') {
            this.setError('api_key', trans.get('validation.required', {attribute: trans.get('keyword.key')}));
        } else if (this.state.id_client === '') {
            this.setError('id_client', trans.get('validation.required', {attribute: trans.get('keyword.id_client')}));
        } else if (this.state.client_secret === '') {
            this.setError('client_secret', trans.get('validation.required', {attribute: trans.get('keyword.client_secret')}));
        } else {
            this.updateState('isLoading', true);
            fetch(URL_API_KEY_CREATE, 'post', {
                api_key: this.state.api_key,
                id_client: this.state.id_client,
                client_secret: this.state.client_secret
            })
                .then(result => {
                    setTimeout(() => {
                        this.updateState('isLoading', false);
                        this.submitResult(result.data.body);
                    }, TIME_OUT_REQUEST);
                })
                .catch(error => {
                    setTimeout(() => {
                        this.updateState('isLoading', false);
                        console.log(error);
                    }, TIME_OUT_REQUEST);
                });
        }
    }
    handlerChangeDelete = (row, e) => {
        let newDeletes = this.state.deletes;
        if (e.target.checked) {
            if (newDeletes.indexOf(row.original.id) === -1) {
                newDeletes.push(row.original.id);
                this.updateState('deletes', newDeletes);
            }
        } else {
            let index = newDeletes.indexOf(row.original.id);
            if (index > -1) {
                newDeletes.splice(index, 1);
                this.updateState('deletes', newDeletes);
            }
        }
    }

    handlerChangePrimary = (row, e) => {
        const api_key = this.state.keys[row.index].api_key;
        this.updateState('loadMore', true);
        fetch(URL_API_KEY_EDIT_PRIMARY, 'put', {api_key: api_key})
            .then(result => {
                setTimeout(() => {
                    this.updateState('loadMore', false);
                    if (result.data.body.statusCode === STATUS_CODE_OK) {
                        let newKeys = this.state.keys;
                        newKeys.map((v, index) => {
                            if (v.id === row.original.id) {
                                v.primary = 1;
                            } else {
                                v.primary = 0;
                            }
                        });
                        this.updateState('keys', newKeys);
                    } else {
                        alert(result.data.body.message);
                    }
                }, TIME_OUT_REQUEST);
            })
            .catch(error => {
                setTimeout(() => {
                    this.updateState('loadMore', false);
                    console.log(error);
                }, TIME_OUT_REQUEST);
            });
    }

    submitDelete = () => {
        if (this.state.deletes.length > 0) {
            if (confirm(trans.get('message.confirm_delete'))) {
                let arr = [];
                this.state.keys.map((v, index) => {
                    if(this.state.deletes.indexOf(v.id) > -1){
                        arr.push(v.api_key);
                    }
                });
                this.updateState('loadMore', true);
                fetch(URL_API_KEY_DELETE, 'delete', {items: arr})
                    .then(result => {
                        setTimeout(() => {
                            this.updateState('loadMore', false);
                            if (result.data.body.statusCode === STATUS_CODE_OK) {
                                let newDeletes = this.state.deletes;
                                const newKeys = _.filter(this.state.keys, function(item) {
                                    return newDeletes.indexOf(item.id) === -1;
                                });
                                newDeletes = [];
                                this.updateState('deletes', newDeletes);
                                this.updateState('keys', newKeys);
                            } else {
                                alert(result.data.body.message);
                            }
                        }, TIME_OUT_REQUEST);
                    })
                    .catch(error => {
                        setTimeout(() => {
                            this.updateState('loadMore', false);
                            console.log(error);
                        }, TIME_OUT_REQUEST);
                    });
            }
        } else {
            alert(trans.get('message.no_choose'));
        }
    }

    render() {
        const columns = [
            {
                Header: state => (
                    <Button variant="outline-primary" onClick={this.submitDelete} size="sm"><Icon name="fe fe-trash-2"/></Button>
                ),
                sortable: false,
                filterable: false,
                maxWidth: 55,
                className: 'd-flex justify-content-center',
                Cell: row => (
                    <label className="custom-control custom-checkbox custom-control-inline m-0">
                        <input type="checkbox"
                               className="custom-control-input"
                               name="delete"
                               checked={this.state.deletes.indexOf(row.original.id) > -1}
                               defaultChecked={this.state.deletes.indexOf(row.original.id) > -1}
                               onChange={(e) => this.handlerChangeDelete(row, e)}/>
                        <span className="custom-control-label"/>
                    </label>
                )
            },
            {
                Header: trans.get('keyword.key'),
                accessor: 'api_key',
            },
            {
                Header: trans.get('keyword.id_client'),
                accessor: 'id_client',
            },
            {
                Header: trans.get('keyword.client_secret'),
                accessor: 'client_secret',
            },
            {
                Header: trans.get('keyword.default'),
                sortable: false,
                filterable: false,
                maxWidth: 100,
                className: 'd-flex justify-content-center',
                Cell: row => (
                    <label className="custom-control custom-radio custom-control-inline m-0">
                        <input type="radio"
                               className="custom-control-input"
                               name="primary"
                               checked={row.original.primary === 1}
                               defaultChecked={row.original.primary === 1}
                               value={row.original.id}
                               onChange={(e) => this.handlerChangePrimary(row, e)}/>
                        <span className="custom-control-label"/>
                    </label>
                )
            },
        ];
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_api_key')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="4">
                        <CardLoaderReact isLoading={this.state.isLoading}>
                            {this.state.message ?
                                <Alert onClose={this.hideAlert} dismissible variant={this.state.messageType}>
                                    <p>{this.state.message}</p></Alert> : ''}
                            <InputGroupReact
                                name="key"
                                type="text"
                                label={trans.get('keyword.key')}
                                placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.key')}
                                value={this.state.api_key}
                                error={this.state.err_api_key}
                                onChange={this.handlerChangeApiKey}
                            />
                            <InputGroupReact
                                name="id_client"
                                type="text"
                                label={trans.get('keyword.id_client')}
                                placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.id_client')}
                                value={this.state.id_client}
                                error={this.state.err_id_client}
                                onChange={this.handlerChangeIdClient}
                            />
                            <InputGroupReact
                                name="client_secret"
                                type="text"
                                label={trans.get('keyword.client_secret')}
                                placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.client_secret')}
                                value={this.state.client_secret}
                                error={this.state.err_client_secret}
                                onChange={this.handlerChangeClientSecret}
                            />
                            <Button variant="primary"
                                    onClick={this.submitData}
                            >{trans.get('keyword.add')}</Button>
                        </CardLoaderReact>

                    </Col>
                    <Col lg="8">
                        <CardLoaderReact isLoading={this.state.loadMore}>
                            <ReactTable
                                PaginationComponent={Pagination}
                                noDataText={trans.get('message.no_result')}
                                columns={columns}
                                data={this.state.keys}
                                defaultPageSize={10}
                                className=""
                            />
                        </CardLoaderReact>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('section-api-key')) {
    ReactDOM.render(<PageApiKeyReact/>, document.getElementById('section-api-key'));
}