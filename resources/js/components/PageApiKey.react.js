import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import ReactTable from 'react-table';
import CardLoaderReact from './CardLoader.react';
import InputGroupReact from './InputGroup.react';
import Pagination from './Pagination.react';
import AlertMessage from './AlertMessage.react';
import {URL_API_KEY_CREATE, URL_API_KEY_GET, STATUS_CODE_OK, STATUS_CODE_FIELD_ERROR} from '../util/constant';
import {fetch} from '../util/util';
import {
    Card,
    Button,
} from 'react-bootstrap';

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
            showAlert: false,
            messages: [],
            messageType: 'info'
        }
        this.editPrimary = this.editPrimary.bind(this);
    }
    componentDidMount() {
        this.updateState('loadMore', true);
        fetch(URL_API_KEY_GET, 'get', this.state)
            .then(result => {
                setTimeout(() => {
                    console.log(result);
                    this.updateState('loadMore', false);
                    this.updateState('keys', result.data.body.data);
                }, 2000);
            })
            .catch(error => {
                console.log(error);
                setTimeout(() => {
                    this.updateState('loadMore', false);
                }, 2000);
            });
    }
    showAlert(messages, type){
        this.updateState('messages', messages);
        this.updateState('messageType', type);
        this.updateState('showAlert', true);
    }
    updateState(k, v){
        const newState = this.state;
        newState[k] = v;
        this.setState(newState);
    }
    setError(k, clear){
      if(k === 'api_key'){
          if(clear){
              this.updateState('err_api_key', null);
          }else{
              this.updateState('err_api_key', trans.get('validation.required').replace(':attribute', trans.get('keyword.key')));
          }
      }else if(k === 'id_client'){
          if(clear){
              this.updateState('err_id_client', null);
          }else{
              this.updateState('err_id_client', trans.get('validation.required').replace(':attribute', trans.get('keyword.id_client')));
          }
      }else if(k === 'client_secret'){
          if(clear){
              this.updateState('err_client_secret', null);
          }else{
              this.updateState('err_client_secret', trans.get('validation.required').replace(':attribute', trans.get('keyword.client_secret')));
          }
      }
    }
    handlerChangeApiKey = (v) => {
        if(v === ''){
            this.setError('api_key', false);
        }
        else{
            this.updateState('api_key', v);
            this.setError('api_key', true);
        }

    }
    handlerChangeIdClient = (v) => {
        if(v === ''){
            this.setError('id_client', false);
        }
        else{
            this.updateState('id_client', v);
            this.setError('id_client', true);
        }
    }
    handlerChangeClientSecret = (v) => {
        if(v === ''){
            this.setError('client_secret', false);
        }
        else{
            this.updateState('client_secret', v);
            this.setError('client_secret', true);
        }
    }
    submitData = () => {
        if(this.state.api_key === ''){
            this.setError('api_key', false);
        }else if(this.state.id_client === ''){
            this.setError('id_client', false);
        }else if(this.state.client_secret === ''){
            this.setError('client_secret', false);
        }else{
            this.updateState('isLoading', true);
            fetch(URL_API_KEY_CREATE, 'post', this.state)
                .then(result => {
                    setTimeout(() => {
                        this.updateState('isLoading', false);
                        if(result.data.body.statusCode == STATUS_CODE_OK){
                            let keyNews = this.state.keys;
                            keyNews.push(result.data.body.data);
                            this.updateState('keys', keyNews);
                        }
                    }, 2000);
                })
                .catch(error => {
                    setTimeout(() => {
                        this.updateState('isLoading', false);
                    }, 2000);
                });
        }
    }
    editPrimary = (value) => {
        console.log(value);
    }
    onCloseAlert = () => {
        this.updateState('showAlert', false);
    }
    render() {
        const columns = [
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
                Header: '',
                Cell: row => (
                    <Button variant="secondary" size="sm"  onClick={() => this.editPrimary(row)}>
                        { trans.get('keyword.default')}
                    </Button>
                )
            }
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